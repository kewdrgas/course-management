import { useEffect, useState } from 'react';
import { Table, Typography, Select, Space, message } from 'antd';
import { Link } from 'react-router-dom';
import { fetchCourses } from '../api/courseApi';
import type { Course } from '../types/Course';

const { Title } = Typography;

const CourseList = () => {
  const [courses, setCourses] = useState<Course[]>([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState<string>('all');

  useEffect(() => {
    fetchCourses()
      .then((res) => setCourses(res.data.courses))
      .catch(() => message.error('Failed to fetch courses'))
      .finally(() => setLoading(false));
  }, []);

  const filteredCourses = filter === 'all' ? courses : courses.filter((c) => c.status === filter);

  return (
    <>
      <Space style={{ marginBottom: 16 }}>
        <Title level={2}>Courses</Title>
        <Select value={filter} onChange={setFilter} style={{ width: 200 }}>
          <Select.Option value="all">All</Select.Option>
          <Select.Option value="draft">Draft</Select.Option>
          <Select.Option value="published">Published</Select.Option>
          <Select.Option value="archived">Archived</Select.Option>
        </Select>
      </Space>

      <Table
        dataSource={filteredCourses}
        rowKey="id"
        loading={loading}
        pagination={{ pageSize: 5 }}
        locale={{ emptyText: 'No courses found.' }}
      >
        <Table.Column title="Title" dataIndex="title" key="title" />
        <Table.Column title="Duration (min)" dataIndex="duration" key="duration" />
        <Table.Column title="Status" dataIndex="status" key="status" />
        <Table.Column title="Created At" dataIndex="createdAt" key="createdAt" />
        <Table.Column
          title="Actions"
          key="actions"
          render={(_, record: Course) => <Link to={`/courses/${record.id}`}>View</Link>}
        />
      </Table>
    </>
  );
};

export default CourseList;
