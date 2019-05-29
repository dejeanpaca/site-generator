<?php

Common::$copy_list = ['css', 'js'];
Common::$pages = ['index.html', 'about.html'];

Common::Add('__HEADER__', 'header.html');
Common::Add('__FOOTER__', 'footer.html');

Posts::add('Start', 'start.html', '2019-05-28');
Posts::add('Another one', 'start.html', '2019-05-29');
Posts::add('Where to', 'start.html', '2019-05-30');
