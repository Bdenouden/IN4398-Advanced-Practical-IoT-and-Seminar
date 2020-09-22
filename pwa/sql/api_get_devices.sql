INSERT INTO iot.pages (page_id, page_type, page_name, page_uri, page_login_required, page_user_member_type) VALUES (7, 'api', 'api_get_devices', '/api/get_devices', 0, '');

create table sensor_nodes
(
    id        varchar(20)                            not null,
    added     datetime   default current_timestamp() not null,
    is_active tinyint(1) default 1                   not null,
    constraint sensor_nodes_id_uindex
        unique (id)
);

alter table sensor_nodes
    add primary key (id);

alter table sensor_data
    add constraint sensor_data_sensor_node_id_fk
        foreign key (node_id) references sensor_nodes (id);
