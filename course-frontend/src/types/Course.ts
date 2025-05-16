export type CourseStatus = 'draft' | 'published' | 'archived';

export interface Course {
  id: string;
  title: string;
  description?: string;
  duration: number;
  status: string;
  createdAt: string;
  publishedAt?: string;
}

export type CourseFormData = Omit<Course, 'id' | 'createdAt' | 'publishedAt'>;

