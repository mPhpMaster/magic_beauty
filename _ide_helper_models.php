<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Branch
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name_en
 * @property string $name_ar
 * @property string|null $location
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $name
 * @property-read mixed $status_text
 * @property-read string $user_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Branch by(string $column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch byActive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch byInactive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch byName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch byNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch byStatus($value, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch byUser($user_id)
 * @method static \Database\Factories\BranchFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUserId($value)
 */
	class Branch extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Category
 *
 * @package App\Models
 * @property int $id
 * @property int $category_id
 * @property string $name_en
 * @property string $name_ar
 * @property string|null $description
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $categories
 * @property-read int|null $categories_count
 * @property-read Category $category
 * @property-read mixed $image
 * @property-read string $image_url
 * @property-read string|null $name
 * @property-read mixed $status_text
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category by(string $column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category byActive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category byCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category byInactive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category byName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category byNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category byStatus($value, $type = null)
 * @method static \Database\Factories\CategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\Favorite
 *
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite by(string $column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite byProduct($user_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite byUser($user_id)
 * @method static \Database\Factories\FavoriteFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereUserId($value)
 */
	class Favorite extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Order
 *
 * @package App\Models
 * @property int $id
 * @property int|null $pay_type_id
 * @property int|null $branch_id
 * @property float|null $sub_total
 * @property float|null $total
 * @property string|null $note
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read mixed $branch_name
 * @property-read string $pay_type_name
 * @property-read mixed $status_text
 * @property-read \App\Models\PayType|null $pay_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order by(string $column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order byActive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Order byBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order byFailed(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Order byInactive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Order byStatus($value, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Order bySuccess(?string $type = null)
 * @method static \Database\Factories\OrderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePayTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PayType
 *
 * @package App\Models
 * @property int $id
 * @property string $name_ar
 * @property string $name_en
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $name
 * @property-read mixed $status_text
 * @method static \Illuminate\Database\Eloquent\Builder|PayType by(string $column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType byActive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType byInactive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType byName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType byNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType byStatus($value, $type = null)
 * @method static \Database\Factories\PayTypeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PayType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayType whereUpdatedAt($value)
 */
	class PayType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Prescription
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $pharmacist_id
 * @property int $patient_id
 * @property string|null $notes
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $doctor
 * @property-read mixed $doctor_name
 * @property-read mixed $patient_mobile
 * @property-read mixed $patient_name
 * @property-read mixed $pharmacist_name
 * @property-read mixed $status_text
 * @property-read \App\Models\User $patient
 * @property-read \App\Models\User $pharmacist
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PrescriptionHistory[] $prescription_histories
 * @property-read int|null $prescription_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription by(string $column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription byActive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription byAnyUser($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription byCanceled(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription byDoctor($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription byFinished(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription byInactive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription byPatient($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription byPending(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription byPharmacist($id)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription byStatus($value, $type = null)
 * @method static \Database\Factories\PrescriptionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription wherePharmacistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereUpdatedAt($value)
 */
	class Prescription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PrescriptionHistory
 *
 * @property int $id
 * @property int $prescription_id
 * @property int $doctor_id
 * @property int $pharmacist_id
 * @property int $patient_id
 * @property string|null $notes
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $doctor
 * @property-read mixed $doctor_name
 * @property-read mixed $patient_mobile
 * @property-read mixed $patient_name
 * @property-read mixed $pharmacist_name
 * @property-read mixed $status_text
 * @property-read \App\Models\User $patient
 * @property-read \App\Models\User $pharmacist
 * @property-read \App\Models\Prescription $prescription
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory by(string $column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byActive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byAnyUser($id)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byCanceled(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byDoctor($id)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byFinished(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byInactive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byPatient($id)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byPending(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byPharmacist($id)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byPrescription(int $id)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory byStatus($value, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory wherePharmacistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory wherePrescriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionHistory whereUpdatedAt($value)
 */
	class PrescriptionHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property int $category_id
 * @property string $name_en
 * @property string $name_ar
 * @property string|null $description
 * @property float|null $price
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $need_prescription
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Branch[] $branches
 * @property-read int|null $branches_count
 * @property-read \App\Models\Category $category
 * @property-read mixed $category_name
 * @property-read mixed $image
 * @property-read string $image_url
 * @property-read string|null $name
 * @property-read mixed $status_text
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Prescription[] $prescriptions
 * @property-read int|null $prescriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product by(string $column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product byActive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product byCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product byInactive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Product byName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product byNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product byProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product byStatus($value, $type = null)
 * @method static \Database\Factories\ProductFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereNeedPrescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * Class Role
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent implements \App\Interfaces\IRoleConst {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name_en
 * @property string $name_ar
 * @property string $email
 * @property string $mobile
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $status
 * @property int|null $created_by
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $device_token
 * @property string|null $location
 * @property-read \App\Models\Branch|null $branch
 * @property-read User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Favorite[] $favorites
 * @property-read int|null $favorites_count
 * @property-read mixed $image
 * @property-read string $image_url
 * @property-read string|null $name
 * @property-read string $role_name
 * @property-read mixed $status_text
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User by(string $column, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|User byActive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User byInactive(?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User byMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User byName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User byNameOrMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User byNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User byStatus($value, $type = null)
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyAdministrators()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyDoctors()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyPatients()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyPharmacists()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlySupports()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

