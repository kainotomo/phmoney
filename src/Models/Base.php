<?php

namespace Kainotomo\PHMoney\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class Base extends Model
{
    protected $connection = 'phmoney_portfolio';

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->setKeyName('pk');

        parent::__construct($attributes);
    }

    protected $guarded = []; // YOLO

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->user() && !$model->team_id) {
                $model->team_id = auth()->user()->current_team_id;
            }
            $model->guid = self::uuid();
        });
    }

    /**
     * Get a new query builder that doesn't have any global scopes or eager loading.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newModelQuery()
    {
        if (auth()->user()) {
            return parent::newModelQuery()->where('team_id', auth()->user()->current_team_id);
        } else {
            return parent::newModelQuery();
        }
    }

    /**
     * Belongs to Team
     *
     * @author Panayiotis Halouvas <phalouvas@kainotomo.com>
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get ordered uuid
     *
     * @author Panayiotis Halouvas <phalouvas@kainotomo.com>
     *
     * @return string
     */
    public static function uuid()
    {
        $uuid = Uuid::uuid1();
        return $uuid->getTimeHiAndVersionHex()
            . $uuid->getTimeMidHex()
            . $uuid->getTimeLowHex()
            . $uuid->getClockSeqHiAndReservedHex() . $uuid->getClockSeqLowHex()
            . $uuid->getNodeHex();
    }

    /**
     * Set portfolio database connection to sqlite
     *
     * @param int $team_id
     * @return void
     */
    public static function setSqliteConnection(int $team_id = null) {

        $team_id = $team_id ?? request()->user()->currentTeam->id;
        $sqlite = config('database.connections.sqlite');
        Config::set("database.connections.sqlite", [
            'driver' => $sqlite['driver'],
            'url' => $sqlite['url'],
            'database' => $sqlite['database'] . "$team_id.sqlite",
            'prefix' => $sqlite['prefix'],
            'foreign_key_constraints' => $sqlite['foreign_key_constraints'],
        ]);

    }

    /**
     * Delete mariadb entries for team
     * @param int $team_id
     * @return void
     */
    public static function deleteMariadb(int $team_id) {
        Base::setSqliteConnection($team_id);

        $tables = Schema::connection('phmoney_portfolio')->getAllTables();
        foreach ($tables as $table) {
            $table_name = $table->Tables_in_phmoney_portfolio;
            if ($table_name === 'migrations') {
                continue;
            }
            DB::connection('phmoney_portfolio')->table($table_name)->where('team_id', auth()->user()->current_team_id)->delete();
        }
    }

    /**
     * Convert sqlite database to mariadb
     * @param int $team_id
     * @return void
     */
    public static function sqlite2mariadb(int $team_id) {
        Base::setSqliteConnection($team_id);

        $tables = Schema::connection('phmoney_portfolio')->getAllTables();
        foreach ($tables as $table) {
            $table_name = $table->Tables_in_phmoney_portfolio;
            if ($table_name === 'migrations' || $table_name === 'settings') {
                continue;
            }
            DB::connection('phmoney_portfolio')->table($table_name)->where('team_id', $team_id)->delete();
            DB::connection('sqlite')->table($table_name)->orderBy('guid')->chunk(200, function ($values) use ($table_name, $team_id) {
                $inserts = [];
                foreach ($values as $value) {
                    $insert = json_decode(json_encode($value), true);
                    $insert['team_id'] = $team_id;
                    $inserts[] = $insert;
                }
                DB::connection('phmoney_portfolio')->table($table_name)->insert($inserts);
            });
        }
    }

    /**
     * Convert mariadb database to sqlite
     * @param int $team_id
     * @return void
     */
    public static function mariadb2sqlite(int $team_id) {
        Base::setSqliteConnection($team_id);

        $tables = Schema::connection('phmoney_portfolio')->getAllTables();
        foreach ($tables as $table) {
            $table_name = $table->Tables_in_phmoney_portfolio;
            if ($table_name === 'migrations') {
                continue;
            }
            DB::connection('sqlite')->table($table_name)->delete();
            DB::connection('phmoney_portfolio')->table($table_name)->where('team_id', $team_id)->orderBy('pk')->chunk(200, function ($values) use ($table_name) {
                $inserts = [];
                foreach ($values as $value) {
                    $insert = json_decode(json_encode($value), true);
                    unset($insert['pk']);
                    unset($insert['team_id']);
                    $inserts[] = $insert;
                }
                DB::connection('sqlite')->table($table_name)->insert($inserts);
            });
        }
    }
}
