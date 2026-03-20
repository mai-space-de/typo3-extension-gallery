CREATE TABLE tx_gallery_domain_model_gallery (
    title varchar(255) NOT NULL DEFAULT '',
    date date DEFAULT NULL,
    project_reference varchar(255) NOT NULL DEFAULT '',
    category int(11) unsigned NOT NULL DEFAULT '0',
    media int(11) unsigned NOT NULL DEFAULT '0',
    description text,
    slug varchar(2048),
    sorting int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_gallery_domain_model_gallerycategory (
    title varchar(255) NOT NULL DEFAULT '',
    description text,
    slug varchar(2048),
    sorting int(11) unsigned NOT NULL DEFAULT '0'
);
