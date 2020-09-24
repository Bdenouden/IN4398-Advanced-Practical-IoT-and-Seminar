create table users
(
    user_id         int auto_increment
        primary key,
    user_name       varchar(255)                not null,
    user_password   varchar(255)                not null,
    user_type       varchar(255) default 'user' not null,
    user_last_login varchar(255)                null
)
    charset = latin1;


