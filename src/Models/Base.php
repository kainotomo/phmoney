<?php

namespace Kainotomo\PHMoney\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\Uuid;

class Base extends Model
{
    protected $connection = 'mysql_portfolio';

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
            $model->team_id = auth()->user()->current_team_id;
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
        return parent::newModelQuery()->where('team_id', auth()->user()->current_team_id);
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


}
