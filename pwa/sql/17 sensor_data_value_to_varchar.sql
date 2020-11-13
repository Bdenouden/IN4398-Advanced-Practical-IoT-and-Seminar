alter table sensor_data modify value varchar(20) not null;

alter table sensor_data change node_id link_id varchar(20) not null;

alter table sensor_data drop foreign key sensor_data_sensor_node_id_fk;

alter table sensor_data drop column type;

alter table sensor_data modify link_id int not null;

alter table sensor_data
    add constraint sensor_data_sensor_node_link_id_fk
        foreign key (link_id) references sensor_node_link (id);

