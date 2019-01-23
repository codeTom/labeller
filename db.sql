CREATE TABLE images (image_id int not null auto_increment,
                     url varchar(128),
                     type varchar(64),
                     filename varchar(64),
                     process int,
                     added datetime,
                     priority int,
                     primary key(image_id));

CREATE TABLE labels (label_id int not null auto_increment,
                     image_id int not null,
                     username varchar(64),
                     type varchar(64),
                     x1 int, x2 int,y1 int, y2 int,
                     updated datetime,
                     primary key (label_id));
