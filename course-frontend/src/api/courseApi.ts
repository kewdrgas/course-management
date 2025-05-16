import axios from 'axios';
import type { Course } from '../types/Course';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8080/api',
});

export const fetchCourses = (page = 1, status = '') =>
    api.get<{ courses: Course[]; total: number; page: number; limit: number }>(`/courses?page=${page}&status=${status}`);

export const fetchCourse = (id: string) =>
    api.get<Course>(`/courses/${id}`);

export const createCourse = (data: Partial<Course>) =>
    api.post('/courses', data);

export const editCourse = (id: string, data: Partial<Course>) =>
    api.post(`/courses/${id}/edit`, data);

export const publishCourse = (id: string) =>
    api.post(`/courses/${id}/publish`);

export const archiveCourse = (id: string) =>
    api.post(`/courses/${id}/archive`);

export const duplicateCourse = (id: string) =>
    api.post(`/courses/${id}/duplicate`);

export const fetchAverageDuration = () =>
    api.get<{ average_duration: number }>('/courses/average-duration');

export const fetchPublishedInRange = (from: string, to: string) =>
    api.get<Course[]>(`/courses/published-by-date-range?from=${from}&to=${to}`);
