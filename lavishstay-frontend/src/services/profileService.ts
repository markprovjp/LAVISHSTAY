import axiosInstance from '../config/axios';

export interface UserProfile {
    id: number;
    name: string;
    email: string;
    phone?: string | null;
    address?: string | null;
    avatar?: string | null;
    profile_photo_path?: string | null;
    roles?: string[];
}

export interface UpdateProfileData {
    name?: string;
    phone?: string | null;
    address?: string | null;
}

export interface ChangePasswordData {
    current_password: string;
    new_password: string;
    new_password_confirmation: string;
}

const profileService = {
    getCurrentUser: async (): Promise<UserProfile> => {
        const res = await axiosInstance.get('/profile/me');
        return res.data.data.user;
    },

    updateProfile: async (data: UpdateProfileData): Promise<UserProfile> => {
        const res = await axiosInstance.put('/profile', data);
        return res.data.data.user;
    },

    uploadAvatar: async (file: File): Promise<UserProfile> => {
        const form = new FormData();
        form.append('avatar', file);
        const res = await axiosInstance.post('/profile/avatar', form, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        return res.data.data.user;
    },

    deleteAvatar: async (): Promise<UserProfile> => {
        const res = await axiosInstance.delete('/profile/avatar');
        return res.data.data.user;
    },

    changePassword: async (data: ChangePasswordData): Promise<void> => {
        await axiosInstance.post('/profile/change-password', data);
    }
};

export default profileService;
