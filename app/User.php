<?php

namespace App;

use App\OAuth\ESI\Authentication;
use App\OAuth\ESI\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * App\User
 *
 * @property string $access_token
 * @property string $authorization_code
 * @property string $character_id
 * @property string $character_name
 * @property \Carbon\Carbon $created_at
 * @property string $email
 * @property string $expire_datetime
 * @property int $id
 * @property string $refresh_token
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAccessToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAuthorizationCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCharacterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCharacterName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereExpireDatetime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRefreshToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 */
class User extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    /**
     * Verify if the users Authorization code is correct, by getting the access token.
     * @return bool
     */
    public function verifyAuthorizationCode()
    {
        if (empty($this->authorization_code)) {
            return false;
        }
        $auth_data = Authentication::verifyAuthorizationCode($this->authorization_code);
        if ($auth_data != false) {
            $this->refresh_token = $auth_data->refresh_token;
            $this->access_token = $auth_data->access_token;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function refreshAccessToken()
    {
        if (empty($this->refresh_token)) {
            Log::info(
                "User has no refresh token",
                ['this' => $this->toArray()]
            );
            return false;
        }
        $auth_data = Authentication::refreshAccessToken($this->refresh_token);
        if ($auth_data != false) {
            Log::info(
                "User refreshed token",
                ['auth_data' => $auth_data, 'getAuthIdentifier' => $this->getAuthIdentifier()]
            );
            $this->refresh_token = $auth_data->refresh_token;
            $this->access_token = $auth_data->access_token;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Get Character ID and name by verifying the access token.
     * After receiving the data, we save this data on the user,
     * because almost every call is based upon the Character ID.
     * Saving the Character Name, because it's nice to show the user has selected the correct user.
     *
     * @return bool
     */
    public function getCharacterData()
    {
        if (empty($this->access_token)) {
            return false;
        }
        $delete_old_user = true;
        $character_data = Authentication::verifyAccessToken($this->access_token);
        if ($character_data != false && isset($character_data->CharacterID)) {
            // Transfer to the old user.
            $old_user = User::whereCharacterId($character_data->CharacterID)->first();
            if ($old_user === null) {
                $old_user = $this;
                $delete_old_user = false;
            }
            $old_user->access_token = $this->access_token;
            $old_user->refresh_token = $this->refresh_token;
            $old_user->authorization_code = $this->authorization_code;
            $old_user->refresh_token = $this->refresh_token;
            if ($delete_old_user) {
                // delete the user that was created
                $this->delete();
            }
            $old_user->character_id     = $character_data->CharacterID;
            $old_user->character_name   = $character_data->CharacterName;
            $old_user->save();
            Auth::login($old_user);
            return true;
        }
        return false;
    }

    /**
     * Returns validation of the current user.
     * This checks the authorization code of the user, and fills the refresh code.
     * After it will try to receive the character data.
     *
     * @return bool|string
     */
    public function validate()
    {
        $verified = $this->verifyAuthorizationCode();
        if ($verified === false) {
            Log::error("Failed to authorization code");
            return __('Verification of authorization code failed, please try again.');
        }
        $character_data_received = $this->getCharacterData();
        if ($character_data_received === false) {
            Log::error("Failed to receive character data");
            return __('Could not receive character data, please try again.');
        }
        return true;
    }

    /**
     * @return array|bool|mixed
     */
    public function getWalletTransactions()
    {
        return Wallet::getTransactions($this->character_id);
    }

    /**
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'character_id';
    }

    /**
     * @return string
     */
    public function getAuthIdentifier()
    {
        return $this->character_id;
    }

    /**
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->access_token;
    }

    /**
     * @return string
     */
    public function getRememberToken()
    {
        return 'remembertoken';
    }

    /**
     * @param string $value
     * @return string
     */
    public function setRememberToken($value = '123')
    {
        return $value;
    }

    /**
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'access_token';
    }


}
