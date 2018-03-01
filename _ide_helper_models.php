<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\TdoTransLog
 *
 * @property int $id
 * @property string $name
 * @property string $date
 * @property int $flag
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransLog whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransLog whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransLog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransLog whereUpdatedAt($value)
 */
	class TdoTransLog extends \Eloquent {}
}

namespace App{
/**
 * App\TdoTransTable
 *
 * @property int $id
 * @property string $name
 * @property string $field
 * @property string|null $primary
 * @property int $verify
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransTable whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransTable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransTable whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransTable wherePrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TdoTransTable whereVerify($value)
 */
	class TdoTransTable extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

