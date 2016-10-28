<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Configuration\Configuration;
use Jalle19\StatusManager\Database\Input;
use Jalle19\StatusManager\Database\InputQuery;
use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InputSeenEvent;
use Jalle19\StatusManager\Event\PersistInputErrorEvent;
use Jalle19\StatusManager\Instance\InputErrorCumulative;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class InputErrorManager
 * @package   Jalle19\StatusManager\Manager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InputErrorManager extends AbstractManager implements EventSubscriberInterface
{

	/**
	 * @var \SplObjectStorage
	 */
	private $_inputErrorCollection;


	/**
	 * @inheritdoc
	 */
	public function __construct(Configuration $configuration, LoggerInterface $logger, EventDispatcher $eventDispatcher)
	{
		parent::__construct($configuration, $logger, $eventDispatcher);

		$this->_inputErrorCollection = new \SplObjectStorage();
	}


	/**
	 * @inheritdoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			Events::INPUT_SEEN                => [
				// Ensure this runs after all other listeners
				['onInputSeen', -100],
			],
			Events::MAIN_LOOP_TICK            => 'onMainLoopTick',
			Events::SUBSCRIPTION_STATE_CHANGE => 'onSubscriptionStateChange',
		];
	}


	/**
	 * @param InputSeenEvent $event
	 */
	public function onInputSeen(InputSeenEvent $event)
	{
		$inputStatus = $event->getInputStatus();
		$input       = InputQuery::create()->findOneByUuid($inputStatus->uuid);

		// This should definitely never happen
		if ($input === null)
			return;

		if (!$this->_inputErrorCollection->contains($input))
			$this->_inputErrorCollection->attach($input, new InputErrorCumulative());

		/* @var InputErrorCumulative $inputErrors */
		$inputErrors = $this->_inputErrorCollection[$input];
		$inputErrors->accumulate($inputStatus);
	}


	/**
	 * Triggers PERSIST_INPUT_ERROR events for all inputs older than one minute
	 */
	public function onMainLoopTick()
	{
		foreach ($this->_inputErrorCollection as $input)
		{
			/* @var InputErrorCumulative $inputErrors */
			$inputErrors = $this->_inputErrorCollection->getInfo();

			/* @var Input $input */
			if ($inputErrors->getCreated() < new \DateTime('-1 minute'))
				$this->handleInputErrors($input, $inputErrors);
		}
	}


	/**
	 * Triggers PERSIST_INPUT_ERROR events for all currently active inputs
	 */
	public function onSubscriptionStateChange()
	{
		foreach ($this->_inputErrorCollection as $input)
		{
			/* @var Input $input */
			$this->handleInputErrors($input, $this->_inputErrorCollection->getInfo());
		}
	}


	/**
	 * @param Input                $input
	 * @param InputErrorCumulative $inputErrors
	 */
	private function handleInputErrors(Input $input, InputErrorCumulative $inputErrors)
	{
		$this->eventDispatcher->dispatch(Events::PERSIST_INPUT_ERROR,
			new PersistInputErrorEvent($input, $inputErrors));

		$this->_inputErrorCollection->detach($input);
	}

}
