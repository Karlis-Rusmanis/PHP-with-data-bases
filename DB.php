<?php
class DB
{
    private $mysql;
    private $entities = [];

    public function __construct($server_name, $username, $password, $dbname)    //konstruktors izveido pieslēgumu un pārbauda vai tas ir veiksmīgs
    {
        $this->mysql = new mysqli($server_name, $username, $password, $dbname, '3307');

        if ($this->mysql->connect_error) {
            die("Connection failed: " . $this->mysql->connect_error);
        }
    }

    public function __deconstruct() //pārtrauc pieslēgumu un atbrīvo atmiņu
    {
        $this->mysql->close();
    }


    public function fetchAll($table_name)   //izpilda query, lai saņemtu visas kolonnas no tabulas
    {
        $table_name = $this->mysql->escape_string($table_name);
        $result = $this->mysql->query("SELECT * FROM `$table_name`");
        if ($result->num_rows > 0) {    //result ir objekts
            while ($row = $result->fetch_assoc()) { //ar ciklu iziet cauri visam result
                $this->entities[$row["id"]] = $row;    //piešķir ka atslēga masīvam ir id nevis kārtas skaitļi, lai kko izdzēšot utt viss nenobruktu
            }
        } else {
            $this->entities =  [];
        }
    }

    /*
     * Atgriež visu tabulu masīvā
     * 
     * @return array - tukšs vai tabulas datus
     */
    public function getAll()
    {
        return $this->entities;
    }

    public function find($id)   //atrod vai tāds id ir datubāzē un tad return to elementu
    {
        if (array_key_exists($id, $this->entities)) {
            return $this->entities[$id];
        }
        return [];
    }

    /*
     * @param string $table_name
     * @param array $entries[$field_name => $field_value]
     * 
     * @return string
     */

    public function add(string $table_name, array $entries) //  
    {
        $columns = array_keys($entries); //var dabūt visas atslēgas

        $first = true;
        $entry_keys = "";
        $entry_values = "";
        foreach ($entries as $column => $value) {   //šīs visas rindas vajadzīgas, lai sagatavotu $entry_keys un $entry_values priekš sql querya
            if ($first) {
                $entry_keys .= "`" . $column . "`";
                $entry_values .= "'" . $this->mysql->escape_string($value) . "'";
                $first = false;
            } else {
                $entry_keys .= ", `" . $column . "`";
                $entry_values .= ", '" . $this->mysql->escape_string($value) . "'";
            }
        }

        $sql = "INSERT INTO $table_name ($entry_keys) VALUES ($entry_values)";  //pati add darbība sākas no šīs rindas

        if ($this->mysql->query($sql) === true) {
            $id = $this->mysql->insert_id;  //$id ir pēdējā ierakstītā elementa id
            $this->entities[$id] = $entries;   //pievieno ierakstu arī masīvam, jo savādāk tas ir tikai datubāzē saglabāts un netiek uzreiz oievienots masīvam
            $this->entities[$id]['id'] = $id;   //ierakstam, kas tiek pievienots masīvam pieviemo arī id vētību
            return "New record created successfully";
        } else {
            return "Error: " . $sql . "<br>" . $this->mysql->error;
        }
    }

    public function update(string $table_name, array $entries)
    {
        $id = $this->mysql->escape_string($entries['id']);
        unset($entries['id']);
        $entry = '';
        $first = true;

        foreach ($entries as $column => $value) {
            $value = $this->mysql->escape_string($value);

            if ($first) {
                $first = false;
            } else {
                $entry .= ",";
            }

            $entry .= "$column ='" . $value . "'";
        }
        $sql = "UPDATE $table_name SET $entry WHERE id=$id";


        if ($this->mysql->query($sql) === TRUE) {
            $this->entities[$id] = $entries;
            $this->entities[$id]['id'] = $id;
            return true;
        } else {
            return "Error updating record: " . $this->mysql->error;
        }
    }

    public function delete(string $table_name, $id)
    {
        $sql = "DELETE FROM $table_name WHERE id=$id";

        if ($this->mysql->query($sql) === true) {
            unset($this->entities[$id]);
            return true;
        }
        return false;
    }
}
