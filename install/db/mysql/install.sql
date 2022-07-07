create table IF NOT EXISTS b_datatools1c_property (
    ID int not null auto_increment,
    PROPERTY_ID int null,
    PROPERTY_IBLOCK int null,  
    PROPERTY_TYPE text null,     
    FOR_PROPERTY_ID text null, 
    TYPE text null,  
    NAME_1C text null, 
    PROPERTY_HIGHLOAD_TABLE text null,   
    primary key (ID)
);

create table IF NOT EXISTS b_datatools1c_iblock (
    ID int not null auto_increment,
    IBLOCK_ID int null,   
    FOR_PROPERTY_ID text null,  
    TYPE text null, 
    IBLOCK_TYPE text null, 
    primary key (ID)
);

create table IF NOT EXISTS b_datatools1c_discont (
    ID int not null auto_increment,
    DISCONT_ID int null,   
    DISCONT_VALUE text null,  
    TYPE text null,  
    primary key (ID)
);

create table IF NOT EXISTS b_datatools1c_events_hl (
    ID int not null auto_increment,
    EVENTS text null,
    FUNCTIONS text null,
    primary key (ID)
);

create table IF NOT EXISTS b_datatools1c_checks (
    ID int not null auto_increment,
    ORDER_ID int null,
    CHECK_NUMBER text null,
    CHECK_NAME text null,
    primary key (ID)
);
create table IF NOT EXISTS b_datatools1c_profile (
    ID int not null auto_increment,
    USER_ID text null,
    PROFILE_ID text null,
    INN text null,
    PROFILE_TYPE INT,
    primary key (ID)
);
create table IF NOT EXISTS b_datatools1c_order_id_not_export (
    ID int not null auto_increment,
    ORDER_ID int null,
    primary key (ID)
);