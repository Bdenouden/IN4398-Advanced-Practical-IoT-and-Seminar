alter table sensor_data
    add measure_time datetime default current_timestamp() not null;

