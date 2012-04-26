USE encoding_queue;

DROP TABLE IF EXISTS queue;

CREATE TABLE queue(
    media_id int NOT NULL,
    source varchar(255) NOT NULL,
    destination varchar(255) NOT NULL,
    priority int,
    status varchar(30) NOT NULL
);

DROP TABLE IF EXISTS provider;

CREATE TABLE provider(
    id int NOT NULL,
    title varchar(255) NOT NULL,
    Primary KEY (id)
);

DROP TABLE IF EXISTS response_mapper;

CREATE TABLE mapper(
    provider_id int NOT NULL,
    KEY_tag varchar(30) NOT NULL,
    KEY_val varchar(30) NOT NULL,
    mapping_tag varchar(30),
    mapping_val varchar(30),
    FOREIGN KEY (provider_id) REFERENCES provider(id)
);

INSERT INTO response_mapper
    (provider_id, key_tag, key_value, mapping_key, mapping_value)
VALUES
    (1, 'status', 'finished', 'status', 'finished'),
    (1, 'status', 'error', 'status', 'error')
