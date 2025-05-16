import { useEffect, useState } from 'react';
import axios from 'axios';
import { Course } from '../types/Course';

type CourseDetailsProps = {
  id: string;
};

const CourseDetails = ({ id }: CourseDetailsProps) => {
  const [course, setCourse] = useState<Course | null>(null);

  useEffect(() => {
    axios.get(`http://localhost:8080/api/courses/${id}`).then((res) => setCourse(res.data));
  }, [id]);

  if (!course) return <p>Loading...</p>;

  return (
    <div>
      <h2>{course.title}</h2>
      <p>{course.description}</p>
      <p>Duration: {course.duration} min</p>
      <p>Status: {course.status}</p>
      <p>Created: {course.createdAt}</p>
    </div>
  );
};

export default CourseDetails;
