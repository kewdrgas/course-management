import { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Typography, message } from 'antd';
import CourseForm from '../components/CourseForm';
import { fetchCourse, editCourse } from '../api/courseApi';
import type { Course, CourseFormData } from '../types/Course';

const { Title } = Typography;

const EditCoursePage = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [initialValues, setInitialValues] = useState<CourseFormData | null>(null);

  useEffect(() => {
    if (id) {
      fetchCourse(id)
          .then(res => setInitialValues(res.data))
          .catch(() => message.error('Failed to load course'));
    }
  }, [id]);

  const handleSubmit = async (data: CourseFormData) => {
    try {
      if (id) await editCourse(id, data);
      message.success('Course updated');
      navigate(`/courses/${id}`);
    } catch {
      message.error('Update failed');
    }
  };

  if (!initialValues) return <p>Loading...</p>;

  return (
      <div>
        <Title level={2}>Edit Course</Title>
        <CourseForm onSubmit={handleSubmit} initialValues={initialValues} />
      </div>
  );
};

export default EditCoursePage;