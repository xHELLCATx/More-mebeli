<?php

namespace app\components;

class Helper
{
    public static function formatDate($date)
    {
        $months = [
            'January' => 'января',
            'February' => 'февраля',
            'March' => 'марта',
            'April' => 'апреля',
            'May' => 'мая',
            'June' => 'июня',
            'July' => 'июля',
            'August' => 'августа',
            'September' => 'сентября',
            'October' => 'октября',
            'November' => 'ноября',
            'December' => 'декабря'
        ];

        if (is_string($date)) {
            $date = new \DateTime($date);
        }

        $formatted = $date->format('d F Y H:i');
        
        foreach ($months as $eng => $rus) {
            $formatted = str_replace($eng, $rus, $formatted);
        }

        return $formatted;
    }
}
