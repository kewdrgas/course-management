import { Form, Input, InputNumber, Button, Select } from 'antd';
import { useEffect } from 'react';
import type { CourseFormData } from '../types/Course';

interface Props {
    onSubmit: (data: CourseFormData) => void;
    initialValues?: CourseFormData;
}

const CourseForm = ({ onSubmit, initialValues }: Props) => {
    const [form] = Form.useForm();

    useEffect(() => {
        if (initialValues) form.setFieldsValue(initialValues);
    }, [initialValues]);

    return (
        <Form form={form} layout="vertical" onFinish={onSubmit}>
            <Form.Item name="title" label="Title" rules={[{ required: true }]}> <Input /> </Form.Item>
            <Form.Item name="description" label="Description"> <Input.TextArea /> </Form.Item>
            <Form.Item name="duration" label="Duration (minutes)" rules={[{ required: true, type: 'number', min: 1 }]}> <InputNumber /> </Form.Item>
            <Form.Item name="status" label="Status" initialValue="draft">
                <Select>
                    <Select.Option value="draft">Draft</Select.Option>
                    <Select.Option value="published">Published</Select.Option>
                    <Select.Option value="archived">Archived</Select.Option>
                </Select>
            </Form.Item>
            <Button type="primary" htmlType="submit">Save</Button>
        </Form>
    );
};

export default CourseForm;