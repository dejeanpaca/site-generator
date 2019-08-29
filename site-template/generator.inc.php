<?php

Common::$copy_list = ['css', 'js'];

Common::Add('__HEADER__', 'header.html');
Common::Add('__FOOTER__', 'footer.html');

Pages::add_post('Start', 'start.html', '2019-05-28');
Pages::add_post('Another one', 'start.html', '2019-05-29');
Pages::add_post('Where to', 'start.html', '2019-05-30');

Pages::add_page('About', 'about.html');
