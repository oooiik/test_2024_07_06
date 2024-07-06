create table testdatabase.customer_to_manager_assign
(
    customer_id int          not null comment 'ID Клиента',
    city_id     int          not null comment 'ID города',
    manager_id  int          not null comment 'ID менеджера',
    created_at  datetime     null comment 'Дата назначения менеджера Клиенту',
    comment     varchar(255) null comment 'комментарий, который можно оставить',
    primary key (customer_id, city_id, manager_id)
);

