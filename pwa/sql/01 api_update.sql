INSERT INTO iot.pages (page_id, page_type, page_name, page_uri, page_login_required, page_user_member_type) VALUES (6, 'api', 'api_update', '/api/update', 0, '');

drop table sensor_data;

create table sensor_data
(
    id         int auto_increment
        primary key,
    value      float                                not null,
    unit       varchar(10)                          not null,
    entry_time datetime default current_timestamp() not null,
    sensor_id  varchar(10)                          not null,
    type       varchar(40)                          not null,
    node_id    varchar(20)                          not null
);

