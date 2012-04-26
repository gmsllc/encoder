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
    primary key (id)
);

DROP TABLE IF EXISTS response_mapper;

CREATE TABLE mapper(
    provider_id int NOT NULL,
    provider_tag varchar(30) NOT NULL,
    provider_val varchar(30) NOT NULL,
    mapping_tag varchar(30),
    mapping_val varchar(30),
    FOREIGN KEY (provider_id) REFERENCES provider(id)
);

INSERT INTO response_mapper
    (provider_id, provider_tag, provider_val, mapping_tag, mapping_val)
VALUES
    (1, 'status', 'finished', 'status', 'finished'),
    (1, 'status', 'error', 'status', 'error')
