import { useNavigate } from 'react-router-dom';
import { message, Typography } from 'antd';
import CourseForm from '../components/CourseForm';
import { createCourse } from '../api/courseApi';
import type { CourseFormData } from '../types/Course';

const { Title } = Typography;

const NewCourse = () => {
  const navigate = useNavigate();

  const handleSubmit = async (data: CourseFormData) => {
    try {
      await createCourse(data);
      message.success('Course created');
      navigate('/courses');
    } catch {
      message.error('Failed to create course');
    }
  };

  return (
      <div>
        <Title level={2}>Create New Course</Title>
        <CourseForm onSubmit={handleSubmit} />
      </div>
  );
};

export default NewCourse;