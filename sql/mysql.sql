CREATE TABLE ac_counter (
    id        INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    uid       INT(5)           NOT NULL DEFAULT '0',
    counter   INT(8)           NOT NULL,
    remote    VARCHAR(250),
    host      VARCHAR(250),
    referer   TEXT,
    useragent VARCHAR(250),
    pageid    VARCHAR(250),
    days      TIMESTAMP(14),
    inday     DATE,
    PRIMARY KEY (id),
    KEY (counter),
    KEY pageid (pageid)
)
    ENGINE = ISAM;

CREATE TABLE just_counter (
    jid     INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    uid     INT(5)          NOT NULL DEFAULT '0',
    counter INT(8)          NOT NULL,
    pageid  VARCHAR(250),
    days    TIMESTAMP(14),
    PRIMARY KEY (jid),
    KEY uid (uid)
)
    ENGINE = ISAM;

