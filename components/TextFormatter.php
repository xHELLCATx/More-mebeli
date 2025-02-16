<?php

namespace app\components;

class TextFormatter
{
    public static function formatText($text)
    {
        // Удаляем множественные пустые строки, оставляя не более одной
        $text = preg_replace('/\n\s*\n\s*\n/', "\n\n", $text);
        
        // Заменяем ** на <strong> для жирного текста
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        
        // Заменяем дефис на длинное тире в тексте, но не в HTML-тегах
        $parts = preg_split('/(<[^>]*>)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($parts as $i => $part) {
            if ($i % 2 === 0) { // Это текст, не тег
                $parts[$i] = str_replace('-', '—', $part);
            }
        }
        $text = implode('', $parts);
        
        // Преобразуем переносы строк в <br> с удалением лишних
        $text = nl2br(trim($text), false);
        
        // Удаляем множественные <br>
        $text = preg_replace('/<br\s*\/?>\s*(<br\s*\/?>\s*)+/', '<br>', $text);
        
        return $text;
    }

    public static function formatCardText($text)
    {
        // Сначала удаляем все HTML теги, если они есть
        $text = strip_tags($text);
        
        // Затем удаляем маркеры жирного текста
        $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text);
        
        // Удаляем множественные пустые строки
        $text = preg_replace('/\n\s*\n\s*\n/', "\n\n", $text);
        
        // Заменяем дефис на длинное тире
        $text = str_replace('-', '—', $text);
        
        // Преобразуем переносы строк в пробелы
        $text = str_replace(["\r\n", "\r", "\n"], ' ', $text);
        
        // Удаляем множественные пробелы
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }
}
