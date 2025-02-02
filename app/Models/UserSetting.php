<?php

namespace App\Models;

use App\Collections\UserSettingsCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $setting
 * @property int $user_id
 */
class UserSetting extends Model
{
    use HasFactory;

    public const string NO_RAFFLE_ENTRY = 'no_raffle_entry';
    public const string USE_PERCENT_INSTEAD_FRACTION = 'use_percent_instead_fraction';
    public const string DONT_SHOW_CREATE_FUNDRAISING = 'dont_show_create_fundraising';
    public const string DONT_SHOW_CREATE_PRIZES = 'dont_show_create_prizes';
    public const string DONT_SHOW_REFERRALS = 'dont_show_referrals';
    public const string DONT_SEND_SUBSCRIBERS_INFORMATION = 'dont_send_subscribers_information';
    protected $fillable = [
        'setting',
        'user_id',
    ];

    public const array SETTINGS_MAP = [
        self::NO_RAFFLE_ENTRY                   => 'Не брати участь в розіграшах',
        self::USE_PERCENT_INSTEAD_FRACTION      => 'Показувати відсотки замість дробі в шансах розіграшів',
        self::DONT_SHOW_CREATE_FUNDRAISING      => 'Не показувати розділ "Збори та Фонди" (не планую створювати збори)',
        self::DONT_SHOW_CREATE_PRIZES           => 'Не показувати розділ "Призи для донаторів" (не планую створювати призи)',
        self::DONT_SHOW_REFERRALS               => 'Не показувати розділ "Запрошені користувачі"',
        self::DONT_SEND_SUBSCRIBERS_INFORMATION => 'Як волонтер: не отримувати повідомлення про додавання/видалення/зміни підписок серійних донатерів',
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSetting(): string
    {
        return $this->setting;
    }

    public function getSettingName(): string
    {
        return self::SETTINGS_MAP[$this->setting] ?? 'Помилка. Налаштування не існує.';
    }

    public function setSetting(string $setting): void
    {
        $this->setting = $setting;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function getSettingsMap(): array
    {
        return self::SETTINGS_MAP;
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param array<int, Model> $models
     * @return \Illuminate\Database\Eloquent\Collection<int, Model>
     */
    public function newCollection(array $models = []): Collection
    {
        return new UserSettingsCollection($models);
    }
}
