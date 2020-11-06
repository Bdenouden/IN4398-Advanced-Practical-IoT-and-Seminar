create table sensor_types
(
    id int auto_increment,
    name varchar(40) not null,
    type varchar(20) not null,
    rawMinVal int not null,
    rawMaxVal int not null,
    minVal int not null,
    maxVal int null,
    constraint sensor_types_pk
        primary key (id)
);

create table sensor_node_link
(
    id int auto_increment,
    node_id varchar(20) null,
    sensor_type_id int null,
    alias varchar(40) null,
    constraint sensor_node_link_pk
        primary key (id),
    constraint sensor_node_link_sensor_nodes_id_fk
        foreign key (node_id) references sensor_nodes (id),
    constraint sensor_node_link_sensor_types_id_fk
        foreign key (sensor_type_id) references sensor_types (id)
);

