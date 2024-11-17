<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAllUsers(): object
    {
        return $this->user->orderBy('id', 'DESC')->get();
    }
     
    public function getUserById(int $id): object
    {
        $user = $this->user->where('id', $id)->first();
        if(!$user) throw new Exception;

        return $user;
        
    }

    public function createUser(array $data): object
    {
        DB::beginTransaction();

        try {

            $user = $this->user->create($data);

            DB::commit();

            return $user;
        
        } catch (Exception $e) {

            DB::rollBack();

            throw new Exception;
        }
    }

    public function updateUser(int $id, array $data): object
    {
        DB::beginTransaction();

        try {

            $user = $this->getUserById($id);            
            $user->update($data);
            
            DB::commit();

            return $user;

        } catch (Exception $e) {

            DB::rollBack();

            throw new Exception;
        }
    }

    public function deleteUser(int $id): object
    {
        try {

            $user = $this->getUserById($id);
            $user->delete();

            return $user;

        } catch (Exception $e) {
            
            throw new Exception;

        }
    }

    public function addAvatar(int $id, Request $request) : object
    {
        $user = $this->getUserById($id);
        
        // Remove old file
        if ($user->avatarUrl)
            $this->removeAvatar($id);
        
        // Save file to public folder
        $filePath = $user->id.'.'.$request->file('file')->extension();       
        $request->file('file')->move(public_path('avatars'), $filePath);

        // Update user object field avatarURL
        $user->avatarUrl = 'avatars/'.$filePath;
        $user->save();

        return $user;
    }

    public function removeAvatar(int $id) : object
    {
        $user = $this->getUserById($id);
        
        // Delete file from public folder
        $file_path = public_path().'/'.$user->avatarUrl;
        unlink($file_path);

        // Update task object field attachmentURL
        $user->avatarUrl = null;
        $user->save();

        return $user;
    }
}