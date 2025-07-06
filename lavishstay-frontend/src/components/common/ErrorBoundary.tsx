import React from 'react';
import { Alert, Button } from 'antd';

interface Props {
    children: React.ReactNode;
    fallback?: React.ReactNode;
}

interface State {
    hasError: boolean;
    error?: Error;
}

class ErrorBoundary extends React.Component<Props, State> {
    constructor(props: Props) {
        super(props);
        this.state = { hasError: false };
    }

    static getDerivedStateFromError(error: Error): State {
        return { hasError: true, error };
    }

    componentDidCatch(error: Error, errorInfo: React.ErrorInfo) {
        console.error('ErrorBoundary caught an error:', error, errorInfo);
    }

    render() {
        if (this.state.hasError) {
            if (this.props.fallback) {
                return this.props.fallback;
            }

            return (
                <Alert
                    message="Có lỗi xảy ra"
                    description="Đã xảy ra lỗi khi hiển thị component này. Vui lòng thử tải lại trang."
                    type="error"
                    showIcon
                    action={
                        <Button
                            size="small"
                            type="primary"
                            onClick={() => window.location.reload()}
                        >
                            Tải lại
                        </Button>
                    }
                    style={{ margin: '16px 0' }}
                />
            );
        }

        return this.props.children;
    }
}

export default ErrorBoundary;
