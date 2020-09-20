create table sensor_data
(
    id         int auto_increment
        primary key,
    value      float       not null,
    unit       varchar(10) not null,
    entry_time datetime    not null,
    sensor_id  int         null
);


