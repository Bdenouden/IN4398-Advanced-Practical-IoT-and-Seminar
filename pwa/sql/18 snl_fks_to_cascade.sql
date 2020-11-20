ALTER TABLE snl_location_link
    DROP CONSTRAINT snl_location_link_sensor_node_link_id_fk;

ALTER TABLE sensor_data
    DROP CONSTRAINT sensor_data_sensor_node_link_id_fk;



ALTER TABLE snl_location_link
    ADD constraint snl_location_link_sensor_node_link_id_fk 
        foreign key (snl_id) references sensor_node_link (id)
        ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE sensor_data
    add constraint sensor_data_sensor_node_link_id_fk
        foreign key (link_id) references sensor_node_link (id)
        ON DELETE CASCADE ON UPDATE CASCADE;