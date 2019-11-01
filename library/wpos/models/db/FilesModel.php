<?php
/**
 * DevicesModel is part of Wallace Point of Sale system (WPOS) API
 *
 * DevicesModel extends the DbConfig PDO class to interact with the config DB table
 *
 * WallacePOS is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * WallacePOS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details:
 * <https://www.gnu.org/licenses/lgpl.html>
 *
 * @package    wpos
 * @copyright  Copyright (c) 2014 WallaceIT. (https://wallaceit.com.au)
 * @link       https://wallacepos.com
 * @author     Michael B Wallace <micwallace@gmx.com>
 * @since      File available since 14/12/13 07:46 PM
 */

class FilesModel extends DbConfig
{

    /**
     * @var array
     */
    protected $_columns = ['id', 'refid', 'userid', 'type', 'description','path', 'dt'];

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $saleid
     * @param $userid
     * @param $type
     * @param $desc
     * @return bool|string Returns false on an unexpected failure, returns -1 if a unique constraint in the database fails, or the new rows id if the insert is successful
     */
    public function create($refid, $userid, $type,$path, $desc='')
    {
        $sql = "INSERT INTO files (refid, userid, type, description,path, dt) VALUES (:saleid, :userid, :type, :desc, :path, :dt)";
        $placeholders = [':refid'=>$refid, ":userid"=>$userid, ":type"=>$type, ":desc"=>$desc,":path"=>$path, ":dt"=>date("Y-m-d H:i:s")];

        return $this->insert($sql, $placeholders);
    }

    /**
     * @param null $saleId
     * @param null $userId
     * @internal param null $deviceId
     * @internal param null $locationId
     * @return array|bool Returns false on an unexpected failure, returns an array of devices on success
     */
    public function get($refid = null, $userId = null)
    {
        $sql = 'SELECT h.* FROM files AS h LEFT JOIN auth AS u on h.userid=u.id';
        $placeholders = [];
        if ($refid !== null) {
            if (empty($placeholders)) {
                $sql .= ' WHERE';
            }
            $sql .= ' h.refid= :refid';
            $placeholders[':refid'] = $refid;
        }
        if ($userId !== null) {
            if (empty($placeholders)) {
                $sql .= ' WHERE';
            }
            $sql .= ' h.userid= :userid';
            $placeholders[':userid'] = $userId;
        }

        return $this->select($sql, $placeholders);
    }

    /**
    * @param $id
    * @return bool|int Returns false on an unexpected failure or the number of rows affected by the operation
    */
    public function removeBySale($id)
    {
        if ($id === null) {
            return false;
        }
        $sql          = "DELETE FROM files WHERE refid= :id;";
        $placeholders = [":id"=>$id];

        return $this->delete($sql, $placeholders);
    }
}
