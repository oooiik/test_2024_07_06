create table testdatabase.managers
(
    id                     int           not null comment 'ID менеджера',
    fio                    varchar(255)  null comment 'ФИО Менеджера ',
    role                   varchar(255)  null comment 'Роль менеджера ',
    efficiency             decimal(7, 2) null comment 'эффективность менеджера',
    attached_clients_count int           null comment 'кол-во прикрепленных Клиентов за менеджером'
);

