<?php


namespace ElemenX\Acquaintances\Traits;

use ElemenX\Acquaintances\Interaction;


/**
 * Trait CanBeFollowed.
 */
trait CanBeFollowed
{
    /**
     * Check if a model is followed by given model.
     *
     * @param  int|array|\Illuminate\Database\Eloquent\Model  $user
     *
     * @return bool
     */
    public function isFollowedBy($user)
    {
        return Interaction::isRelationExists($this, 'followers', $user);
    }

    /**
     * Return followers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->morphToMany(config('acquaintances.model_namespace') . '\\' . config('acquaintances.user_model_class_name'), 'subject',
            config('acquaintances.tables.interactions'))
                    ->wherePivot('relation', '=', Interaction::RELATION_FOLLOW)
                    ->withPivot(...Interaction::$pivotColumns);
    }

    public function followersCount()
    {
        return $this->followers()->count();
    }

    public function getFollowersCountAttribute()
    {
        return $this->followersCount();
    }

    public function followersCountReadable($precision = 1, $divisors = null)
    {
        return Interaction::numberToReadable($this->followersCount(), $precision, $divisors);
    }
}
