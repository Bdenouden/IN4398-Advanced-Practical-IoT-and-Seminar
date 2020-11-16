alter table sensor_types
    add unit varchar(20) not null;

alter table sensor_node_link drop column unit;
