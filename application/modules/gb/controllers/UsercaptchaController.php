<?php

/**
 * Description of UsercaptchaController
 *
 * @author ppd
 */
class UsercaptchaController extends Controller
{
    const IMG_DIR = '/application/modules/gb/views/includes/images/captcha/';
    // Функция генерации капчи
    private function generate_code() 
    {    
        $chars = 'abdefhknrstyz23456789'; // Задаем символы, используемые в капче. Разделитель использовать не надо.
        $length = rand(4, 7); // Задаем длину капчи, в нашем случае - от 4 до 7
        $numChars = strlen($chars); // Узнаем, сколько у нас задано символов
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, rand(1, $numChars) - 1, 1);
        } // Генерируем код

        // Перемешиваем, на всякий случай
            $array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
            srand ((float)microtime()*1000000);
            shuffle ($array_mix);
        // Возвращаем полученный код
        return implode("", $array_mix);
    }
    
    // Пишем функцию генерации изображения
    private function img_code($code) // $code - код нашей капчи, который мы укажем при вызове функции
    {
        // Отправляем браузеру Header'ы
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                   
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", 10000) . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");         
        header("Cache-Control: post-check=0, pre-check=0", false);           
        header("Pragma: no-cache");                                           
        header("Content-Type:image/png");
        // Количество линий. Обратите внимание, что они накладываться будут дважды (за текстом и на текст). Поставим рандомное значение, от 3 до 7.
        $linenum = rand(3, 7); 
        // Задаем фоны для капчи. Можете нарисовать свой и загрузить его в папку /img. Рекомендуемый размер - 150х70. Фонов может быть сколько угодно
        $img_arr = array("1.png");
        // Шрифты для капчи. Задавать можно сколько угодно, они будут выбираться случайно
        $font_arr = array();
        $font_arr[0]["fname"] = "DroidSans.ttf";	// Имя шрифта. Я выбрал Droid Sans, он тонкий, плохо выделяется среди линий.
        $font_arr[0]["size"] = rand(20, 30);				// Размер в pt
        // Генерируем "подстилку" для капчи со случайным фоном
        $n = rand(0,sizeof($font_arr)-1);
        $img_fn = $img_arr[rand(0, sizeof($img_arr)-1)];
        $im = imagecreatefrompng (self::IMG_DIR . $img_fn); 
        // Рисуем линии на подстилке
        for ($i=0; $i<$linenum; $i++)
        {
            $color = imagecolorallocate($im, rand(0, 150), rand(0, 100), rand(0, 150)); // Случайный цвет c изображения
            imageline($im, rand(0, 20), rand(1, 50), rand(150, 180), rand(1, 50), $color);
        }
        $color = imagecolorallocate($im, rand(0, 200), 0, rand(0, 200)); // Опять случайный цвет. Уже для текста.

        // Накладываем текст капчи				
        $x = rand(0, 35);
        for($i = 0; $i < strlen($code); $i++) {
            $x+=15;
            $letter=substr($code, $i, 1);
            imagettftext ($im, $font_arr[$n]["size"], rand(2, 4), $x, rand(50, 55), $color, self::IMG_DIR.$font_arr[$n]["fname"], $letter);
        }

        // Опять линии, уже сверху текста
        for ($i=0; $i<$linenum; $i++)
        {
            $color = imagecolorallocate($im, rand(0, 255), rand(0, 200), rand(0, 255));
            imageline($im, rand(0, 20), rand(1, 50), rand(150, 180), rand(1, 50), $color);
        }
        // Возвращаем получившееся изображение
        ImagePNG ($im);
        ImageDestroy ($im);
    }
    
    private function check_code($code, $cookie) 
    {

        // АЛГОРИТМ ПРОВЕРКИ	
        $code = trim($code); // На всякий случай убираем пробелы
        $code = md5($code);
        // НЕ ЗАБУДЬТЕ ЕГО ИЗМЕНИТЬ!

        // Работа с сессией, если нужно - раскомментируйте тут и в captcha.php, удалите строчки, где используются куки
//        session_start();
//        $cap = $_SESSION['captcha'];
//        $cap = md5($cap);
//        session_destroy();

        if ($code == $cap){return TRUE;}else{return FALSE;} // если все хорошо - возвращаем TRUE (если нет - false)

    }
    
    public function captchaAction()
    {
        $captcha = $this->generate_code();

        // Используем сессию (если нужно - раскомментируйте строчки тут и в go.php)
//         session_start();
//         $_SESSION['captcha']=$captcha;
//         session_destroy();

        // Вносим в куки хэш капчи. Куки будет жить 120 секунд.
        $cookie = md5($captcha);
        $cookietime = time()+120; // Можно указать любое другое время
        setcookie("captcha", $cookie, $cookietime);
        $this->img_code($captcha);
    }
    
    public function goAction()
    {
        $cap = $_COOKIE["captcha"]; // берем из куки значение MD5 хэша, занесенного туда в captcha.php
        //$cap = $_SESSION["captcha"];
        // Обрабатываем полученный код
	if (isset($_POST['go'])) // Немного бессмысленная, но все же защита: проверяем, как обращаются к обработчику.
	{
            // Если код не введен (в POST-запросе поле 'code' пустое)...
            if ($_POST['code'] == '')
            {
                    exit(json_encode(['error' => "Ошибка: введите капчу!"])); //...то возвращаем ошибку
            }
	    // Если код введен правильно (функция вернула TRUE), то...
            if ($this->check_code($_POST['code'], $cookie))
            {
                    echo json_encode(['success' => "Ты правильно ввел капчу. Возьми с полки тортик, он твой."]); // Поздравляем с этим пользователя
                    exit;
            }
	    // Если код введен неверно...
            else 
            {
                exit(json_encode(['error' => "Ошибка: капча введена неверно!"])); //...то возвращаем ошибку
            }
        }
	// Если к нам обращаются напрямую, то дело дрянь...
	else 
	{
	    exit(json_encode(['error' => "Access denied"])); //..., возвращаем ошибку
	}
    }
}
