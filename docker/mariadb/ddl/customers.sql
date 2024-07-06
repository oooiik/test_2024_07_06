create table testdatabase.customers
(
    id               int          not null comment 'ID Клиента',
    city_id          int          not null comment 'ID города',
    fio              varchar(255) not null comment 'ФИО Клиента',
    phone            varchar(255) null comment 'телефон Клиента',
    first_order_date int(12)      null comment 'Дата/время первого заказа Клиента',
    last_order_date  date         null comment 'Дата/время последнего заказа Клиента',
    primary key (id, city_id)
);

