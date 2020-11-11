alter table sensor_node_link
    add unit varchar(20) not null;

alter table sensor_data drop column unit;

alter table sensor_data drop column sensor_id;

