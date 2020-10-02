create table triggers
(
    id                   int auto_increment
        primary key,
    link_id              int not null,
    lessThan_greaterThan int not null,
    val                  int not null,
    notification_type    int not null,
    constraint triggers_sensor_node_link_id_fk
        foreign key (link_id) references sensor_node_link (id)
            on delete cascade
);
