<?php
header('Content-Type: text/html; charset=utf-8');

// Função para obter a data como um objeto DateTime
function getDateAsDateTime($date)
{
    return is_string($date) ? new DateTime($date) : $date;
}

// Função para verificar se uma data é fim de semana
function isWeekend($date)
{
    $inputDate = getDateAsDateTime($date);
    return $inputDate->format('N') >= 6;
}

// Função para verificar se uma data ocorre antes de outra
function isBefore($date1, $date2)
{
    $inputDate1 = getDateAsDateTime($date1);
    $inputDate2 = getDateAsDateTime($date2);
    return $inputDate1 <= $inputDate2;
}

// Função para obter o próximo dia
function getNextDay($date)
{
    $inputDate = getDateAsDateTime($date);
    $inputDate->modify('+1 day');
    return $inputDate;
}

// Função para somar dois intervalos de tempo
function sumIntervals($interval1, $interval2)
{
    $date = new DateTime('00:00:00');
    $date->add($interval1);
    $date->add($interval2);
    return (new DateTime('00:00:00'))->diff($date);
}

// Função para subtrair dois intervalos de tempo

function subtractIntervals($interval1, $interval2)
{
    $date = new DateTime('00:00:00');
    $date->add($interval1);
    $date->sub($interval2);
    return (new DateTime('00:00:00'))->diff($date);
}

// Função para obter um objeto DateTimeImmutable de uma string de data
function getDateFromString($str)
{
    return DateTimeImmutable::createFromFormat('H:i:s', $str);
}

// Função para obter o primeiro dia do mês
function getFirstDayOfMonth($date)
{
    $time = getDateAsDateTime($date)->getTimestamp();
    return new DateTime(date('Y-m-1', $time));
}

// Função para obter o último dia do mês
function getLastDayOfMonth($date)
{
    $time = getDateAsDateTime($date)->getTimestamp();
    return new DateTime(date('Y-m-t', $time));
}

// Função para obter o número de segundos de um intervalo de tempo
function getSecondsFromDateInterval($interval)
{
    $d1 = new DateTimeImmutable();
    $d2 = $d1->add($interval);
    return $d2->getTimestamp() - $d1->getTimestamp();
}

// Função para verificar se uma data é um dia útil no passado
function isPastWorkday($date)
{
    return !isWeekend($date) && isBefore($date, new DateTime());
}

// Função para converter o número de segundos em uma string de tempo
function getTimeStringFromSeconds($seconds)
{
    $h = intdiv($seconds, 3600);
    $m = intdiv($seconds % 3600, 60);
    $s = $seconds - ($h * 3600) - ($m * 60);
    return sprintf('%02d:%02d:%02d', $h, $m, $s);
}

// Função para formatar uma data com um padrão de acordo com o local
function formatDateWithLocale($date, $pattern)
{
    $time = getDateAsDateTime($date)->getTimestamp();
    return strftime($pattern, $time);
}
