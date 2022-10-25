DROP TABLE link;

CREATE TABLE link (
    key TEXT primary key,
    url TEXT,
    created_at TEXT,
    lifetime INTEGER,
    last_access TEXT,
    hits INTEGER
);
