import { GoogleGenerativeAI, SchemaType } from '@google/generative-ai';

const genAI = new GoogleGenerativeAI(import.meta.env.VITE_GOOGLE_GENAI_API_KEY);

const schema = {
    type: SchemaType.ARRAY,
    items: {
        type: SchemaType.OBJECT,
        properties: {
            id: {
                type: SchemaType.INTEGER,
                nullable: false
            },
            question: {
                type: SchemaType.STRING,
                nullable: false,
            },
            answers: {
                type: SchemaType.ARRAY,
                nullable: false,
                items: {
                    type: SchemaType.STRING,
                },
                minItems: 2,
                maxItems: 4
            },
            correctAnswer: {
                type: SchemaType.STRING,
                nullable: false
            },
            difficulty: {
                type: SchemaType.STRING,
                nullable: false,
                enum: ["Easy", "Medium", "Hard"]
            }
        },
        required: ['question', 'answers', 'correctAnswer', 'id', 'difficulty'],
    },
};

const modelJson = genAI.getGenerativeModel({
    model: 'gemini-2.0-flash',
    generationConfig: {
        responseMimeType: 'application/json',
        responseSchema: schema
    }
});

/**
 * Tạo câu hỏi trắc nghiệm tự động dựa trên thông số đầu vào
 * @param {string} topic - Chủ đề cần tạo câu hỏi
 * @param {quizName} quizName - Tên quiz
 * @param {number} numberOfQuestions - Số lượng câu hỏi
 * @param {number} answersPerQuestion - Số đáp án mỗi câu
 * @param {string} difficultyLevel - Độ khó (Easy/Medium/Hard)
 * @param {function} callback - Hàm callback nhận kết quả
 */
export async function generateQuizQuestions(topic,quizName ,numberOfQuestions, answersPerQuestion, difficultyLevel, callback) {
    try {
        const prompt = `
        Generate ${numberOfQuestions} multiple-choice questions about "${topic}".
        These questions will be used for a quiz named "${quizName}".
        Each question should have ${answersPerQuestion} answer options with only 1 correct answer.
        Difficulty level: ${difficultyLevel}.
        Format in JSON according to the defined schema.
        IMPORTANT:
        - Do not include A, B, C, D prefixes in the answers
        - Each answer must be clearly distinct
        - Questions must be closely related to the topic
        - Make the questions educational and appropriate for quiz assessment
        `;

        const response = await modelJson.generateContent(prompt);
        const result = await JSON.parse(response.response.text());
        callback(result);
    } catch (error) {
        callback(null, error);
    }
}
