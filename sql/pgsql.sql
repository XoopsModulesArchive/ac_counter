--
-- Table structure for table `ac_counter`
--
CREATE TABLE ac_counter (
    id        SERIAL UNIQUE NOT NULL,
    uid       INTEGER       NOT NULL,
    counter   INTEGER       NOT NULL,
    remote    VARCHAR(250),
    host      VARCHAR(250),
    referer   TEXT,
    useragent VARCHAR(250),
    pageid    VARCHAR(250),
    inday     DATE         DEFAULT current_date,
    days      TIMESTAMP(0) DEFAULT current_timestamp,
    CONSTRAINT ac_counter_pk PRIMARY KEY (id, uid, days)
);
GRANT ALL ON ac_counter TO %s;
-- --------------------------------------------------------
--
-- Table structure for table `just_counter`
--
CREATE TABLE just_counter (
    jid     SERIAL UNIQUE NOT NULL,
    uid     INTEGER       NOT NULL,
    counter INTEGER       NOT NULL,
    pageid  VARCHAR(250),
    days    TIMESTAMP(0) DEFAULT current_timestamp,
    CONSTRAINT just_counter_pk PRIMARY KEY (jid, uid)
);
GRANT ALL ON just_counter TO %s;
