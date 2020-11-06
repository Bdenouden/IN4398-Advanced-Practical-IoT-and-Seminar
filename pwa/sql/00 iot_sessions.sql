create table sessions
(
    session_id         int auto_increment
        primary key,
    user_id            int           not null,
    session_token      varchar(255)  not null,
    session_expiration datetime      not null,
    session_active     int default 1 not null
)
    charset = latin1;


