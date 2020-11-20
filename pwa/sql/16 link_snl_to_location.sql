create table snl_location_link (
    id int auto_increment,
    snl_id int not null,
    x int null,
    y int null,
    created_at datetime not null default current_timestamp(),
    updated_at datetime not null default current_timestamp(),

    constraint snl_location_link_pk 
        primary key (id),
    constraint snl_location_link_sensor_node_link_id_fk 
        foreign key (snl_id) references sensor_node_link (id)
);