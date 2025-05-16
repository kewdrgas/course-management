import { Descriptions, Card, Button, Space, Typography, message } from 'antd';
import { useParams, useNavigate } from 'react-router-dom';
import { useEffect, useState } from 'react';
import { fetchCourse, duplicateCourse, publishCourse, archiveCourse } from '../api/courseApi';
import type { Course } from '../types/Course';

const { Title } = Typography;

const CourseDetailsPage = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [course, setCourse] = useState<Course|null>(null);
  const [loading, setLoading] = useState(true);

  const loadCourse = () => {
    if (!id) return;
    fetchCourse(id)
      .then((res: { data: any; }) => setCourse(res.data))
      .catch(() => message.error('Failed to load course'))
      .finally(() => setLoading(false));
  };

  useEffect(() => {
    loadCourse();
  }, [id]);

  const handlePublish = async () => {
    if (!id) return;
    try {
      await publishCourse(id);
      message.success('Course published');
      loadCourse();
    } catch {
      message.error('Failed to publish');
    }
  };

  const handleArchive = async () => {
    if (!id) return;
    try {
      await archiveCourse(id);
      message.success('Course archived');
      loadCourse();
    } catch {
      message.error('Failed to archive');
    }
  };

  if (loading || !course) return <p>Loading...</p>;

  return (
    <Card style={{ maxWidth: 800, margin: 'auto' }}>
      <Title level={3}>Course Details</Title>

      <Descriptions column={1} bordered>
        <Descriptions.Item label="Title">{course.title}</Descriptions.Item>
        <Descriptions.Item label="Description">{course.description || '-'}</Descriptions.Item>
        <Descriptions.Item label="Duration (minutes)">{course.duration}</Descriptions.Item>
        <Descriptions.Item label="Status">{course.status}</Descriptions.Item>
        <Descriptions.Item label="Created At">
          {new Date(course.createdAt).toLocaleString()}
        </Descriptions.Item>
        {course.status === 'published' && course.publishedAt && (
          <Descriptions.Item label="Published At">
            {new Date(course.publishedAt).toLocaleString()}
          </Descriptions.Item>
        )}
      </Descriptions>

      <Space style={{ marginTop: 24 }}>
        {course.status === 'draft' && (
          <Button type="primary" onClick={handlePublish}>
            Publish
          </Button>
        )}
        {course.status !== 'archived' && (
          <Button danger onClick={handleArchive}>
            Archive
          </Button>
        )}
        {course.status !== 'archived' && (
          <Button onClick={() => navigate(`/courses/${id}/edit`)}>Edit</Button>
        )}
        {course.status !== 'archived' && (
            <Button onClick={async () => {
              try {
                const res = await duplicateCourse(course.id);
                message.success('Course duplicated');
                navigate(`/courses/${res.data.id}`);
              } catch {
                message.error('Failed to duplicate course');
              }
            }}>
              Duplicate
            </Button>
        )}
        <Button onClick={() => navigate('/courses')}>Back to List</Button>
      </Space>
    </Card>
  );
};

export default CourseDetailsPage;
