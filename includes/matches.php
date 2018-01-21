<?php
/**
 * Class Matches
 *
 */
class Matches{

    private $table_name = 'matches';
    private $db;
    public $date;
    private $api_link = 'http://mathces/';

    public function __construct($date = null)
    {
        $this->db = DB::getInstance();
        $this->setDate($date);
        $this->createMatchesTable();
    }


    /**
     * Get remote data from API
     *
     * @return array
     */
    public function getRemoteData()
    {
        $url = 'https://api.fantasydata.net/mlb/v2/json/GamesByDate/'.$this->date;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Ocp-Apim-Subscription-Key: 75690c1c3886439d805fff0f69d9c9de"));
        $res = curl_exec($ch);
        curl_close($ch);
        $games = json_decode($res);
        return $games;
    }

    /**
     * Write data from remote API to local DB
     *
     * @return void
     */
    public function writeDataToDb()
    {
        $data = $this->getRemoteData();
        if (!is_array($data)) return 'Data is empty!';
        foreach ($data as $k=>$v) {
            // check if current match is not in the table

            $game_data = $this->getMatchByStadium($v->GameID);
            if (!empty($game_data)) continue;
            if (count($game_data) === 0) {

                $q = "INSERT INTO `{$this->table_name}`
                    (`gameID`, `season`, `dt`, `awayTeam`, `homeTeam`, `awayTeamID`, `homeTeamID`, `stadiumID`)
                    VALUES(
                      '{$v->GameID}',
                      '{$v->Season}',
                      '{$v->Day}',
                      '{$v->AwayTeam}',
                      '{$v->HomeTeam}',
                      '{$v->AwayTeamID}',
                      '{$v->HomeTeamID}',
                      '{$v->StadiumID}'
                    )
                ";
                $res = $this->db->db_query($q);
                if (!$res) return 'Error!!';
            }
        }
        return 'Data is added to database!';
    }

    /**
     * Get match data
     *
     * @param int $id
     * @return array
     */
    public function getMatchByStadium($id)
    {
        $q = "SELECT * FROM `{$this->table_name}` WHERE `gameID`='{$id}'";
        $res = $this->db->db_query($q);
        if (!$res) return false;
        $row = mysqli_fetch_assoc($res);
        return $row;
    }

    /**
     * Get matches data from local DB
     *
     * @return string json data or error message
     */
    public function getData()
    {
        $date = $this->date;
        if (!empty($date)) {
            $date_new = date_create($date);
            $date = date_format($date_new, 'Y-m');
            $dt = explode('-', $date);
            $year = (!empty($dt))?$dt[0]:'';
            $month = (!empty($dt))?$dt[1]:'';
        }

        $q = "SELECT `dt`, `homeTeam`, `awayTeam`, `stadiumID` FROM `{$this->table_name}` WHERE 1";
        if (!(empty($month)) && !(empty($year))) {
            $q .= " AND MONTH(dt) = '{$month}' AND YEAR(dt) = '{$year}'";
        }
        $q .= " ORDER BY `dt`";

        $res = $this->db->db_query($q);
        if(!$res) return "Error";

        $rows = array();
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }
        return json_encode($rows);

    }

    /**
     * Get data from local API
     *
     * @return array
     */

    public function showData()
    {
        $data = file_get_contents($this->api_link.'api.php?getData');
        $matches = json_decode($data);
        include SITE_PATH.'/templates/matches.php';
    }

    /**
     * Set date value
     *
     * @param string $date
     */
    private function setDate($date)
    {
        $this->date = (!empty($date))?$date:null;
    }

    /**
     * Create DB table if it's not exist
     *
     * @return source|false
     */
    private function createMatchesTable()
    {

        $q = "CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `gameID` INT(11) NOT NULL,
                `season` INT(11) NOT NULL,
                `dt` date NOT NULL,
                `awayTeam` varchar(50) NOT NULL,
                `homeTeam` varchar(50) NOT NULL,
                `awayTeamID` varchar(50) NOT NULL,
                `homeTeamID` varchar(50) NOT NULL,
                `statiumID` int(11) NOT NULL,
                PRIMARY KEY(`id`)
        )";

        $res = $this->db->db_query($q);
        return $res;
    }

}