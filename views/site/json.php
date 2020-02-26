<?php
use yii\helpers\Html;

echo UrlTags(GetRepos($username), $username); // должен показать содержимое будующего файла (показывал первые 60 раз)


function UrlTags($nameReposJsonArr, $nameUser)
{
    $bildJsonFile = "";
    try {
        for ($i0 = 0; $i0 < count($nameReposJsonArr); $i0++) {
            $ch = curl_init("https://api.github.com/repos/$nameUser/" . $nameReposJsonArr[$i0]["name"] . '/tags');
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                [
                    'Accept: application/vnd.github.v3+json',
                    'User-Agent: GitHub-username'
                ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $json = curl_exec($ch);
            $bildJsonFile .= substr($json, 1, strlen($json) - 2);
        }
        $bildJsonFile = "[" . $bildJsonFile . "]";
        Html::encode($bildJsonFile);
    }
    catch (Exception $e) {
        Html::encode($e);
        $bildJsonFile == "-1";
    }
    return $bildJsonFile;
}

function GetRepos($nameUser){
    $ch = curl_init("https://api.github.com/users/$nameUser/repos");
    curl_setopt($ch, CURLOPT_HTTPHEADER,
        [
        'Accept: application/vnd.github.v3+json',
        'User-Agent: GitHub-username'
        ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $json = curl_exec($ch);

    $obj = json_decode($json,true);
    return $obj;
}
?>

<a href="">Скачать</a>
