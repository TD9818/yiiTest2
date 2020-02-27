<?php


class JSON
{
    const ERR = '-1';

    public function GitTags($nameUser)
    {
        $json = GetUrl('repos', $nameUser);
        $nameReposJsonArr = json_decode($json, true);

        $bildJsonFile = "";
        try {
            for ($i0 = 0; $i0 < count($nameReposJsonArr); $i0++) {
                $json = GetUrl('tags', $nameUser, $nameReposJsonArr[$i0]["name"]);
                $bildJsonFile .= substr($json, 1, strlen($json) - 2);
            }
            $bildJsonFile = '[' . $bildJsonFile . ']';
        } catch (Exception $e) {
            $bildJsonFile = ERR;
            return $bildJsonFile;
        }
        return json_decode($bildJsonFile, true);
    }

    private function GetUrl($reposOrTags, $nameUser, $nameReposJsonArr = '')
    {
        if ($reposOrTags === 'tags') {
            $nameUser .= '/';
            $nameReposJsonArr .= '/';
            $ch = curl_init('https://api.github.com/repos/' . $nameUser . $nameReposJsonArr . 'tags');
        } elseif ($reposOrTags === 'repos') {
            $nameUser .= '/';
            $ch = curl_init('https://api.github.com/users/' . $nameUser . 'repos');
        } else {
            return ERR;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER,
            [
                'Accept: application/vnd.github.v3+json',
                'User-Agent: GitHub-username'
            ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        return curl_exec($ch);
    }

}