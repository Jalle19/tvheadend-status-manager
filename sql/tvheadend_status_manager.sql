
-----------------------------------------------------------------------
-- instance
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [instance];

CREATE TABLE [instance]
(
    [name] VARCHAR(255) NOT NULL,
    PRIMARY KEY ([name]),
    UNIQUE ([name])
);

-----------------------------------------------------------------------
-- user
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [user];

CREATE TABLE [user]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [name] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

CREATE INDEX [instance_name_name] ON [user] ([instance_name],[name]);

-----------------------------------------------------------------------
-- connection
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [connection];

CREATE TABLE [connection]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [user_id] INTEGER,
    [peer] VARCHAR(255) NOT NULL,
    [started] TIMESTAMP NOT NULL,
    [type] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name]),
    FOREIGN KEY ([user_id]) REFERENCES [user] ([id])
);

CREATE INDEX [instance_name_peer_started] ON [connection] ([instance_name],[peer],[started]);

-----------------------------------------------------------------------
-- input
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [input];

CREATE TABLE [input]
(
    [uuid] VARCHAR(255) NOT NULL,
    [instance_name] VARCHAR(255) NOT NULL,
    [started] TIMESTAMP NOT NULL,
    [input] VARCHAR(255) NOT NULL,
    [network] VARCHAR(255) NOT NULL,
    [mux] VARCHAR(255) NOT NULL,
    [weight] INTEGER NOT NULL,
    PRIMARY KEY ([uuid]),
    UNIQUE ([uuid]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

-----------------------------------------------------------------------
-- input_error
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [input_error];

CREATE TABLE [input_error]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [input_uuid] VARCHAR(255) NOT NULL,
    [ber_average] DOUBLE NOT NULL,
    [unc_average] DOUBLE NOT NULL,
    [cumulative_te] INTEGER NOT NULL,
    [cumulative_cc] INTEGER NOT NULL,
    [created] TIMESTAMP,
    [modified] TIMESTAMP,
    UNIQUE ([id]),
    FOREIGN KEY ([input_uuid]) REFERENCES [input] ([uuid])
);

-----------------------------------------------------------------------
-- channel
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [channel];

CREATE TABLE [channel]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [name] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

CREATE INDEX [instance_name_name] ON [channel] ([instance_name],[name]);

-----------------------------------------------------------------------
-- subscription
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [subscription];

CREATE TABLE [subscription]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [input_uuid] VARCHAR(255),
    [user_id] INTEGER,
    [channel_id] INTEGER NOT NULL,
    [subscription_id] INTEGER NOT NULL,
    [started] TIMESTAMP NOT NULL,
    [stopped] TIMESTAMP,
    [title] VARCHAR(255) NOT NULL,
    [service] VARCHAR(255),
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name]),
    FOREIGN KEY ([input_uuid]) REFERENCES [input] ([uuid]),
    FOREIGN KEY ([user_id]) REFERENCES [user] ([id]),
    FOREIGN KEY ([channel_id]) REFERENCES [channel] ([id])
);

CREATE INDEX [instance_name_user_id_channel_id_subscription_id_started] ON [subscription] ([instance_name],[user_id],[channel_id],[subscription_id],[started]);
